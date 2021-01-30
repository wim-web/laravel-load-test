from locust import HttpUser, TaskSet, task, constant, SequentialTaskSet
import random

def isExecute(percentage) -> bool:
    return random.random() <= (percentage / 100)

class ArticleTaskSet(SequentialTaskSet):
    
    def __init__(self, parent):
        super().__init__(parent)
        
    wait_time = constant(1)
    
    @task
    def wandering(self):
        page = random.randint(1, 5000)
        for _ in range(random.randint(1, 10)):
            
            page += 1
            self.client.get('/articles?page={}'.format(page), name="index_article")
            
            if isExecute(50):
                articleID = random.randint(1, 100) * page
                self.client.get('/articles/{}'.format(articleID), name="show_article")
                
                if isExecute(50):
                    self.client.post('/articles/{}/like'.format(articleID), None, None, name="like_article")
    
    @task
    def create(self):
        if isExecute(70):
            self.client.post('/user/articles', {'title': 'locust', 'body': 'locust'}, None, name="store_article")
            
    @task
    def my_article(self):
        
        response = self.client.get('/user/articles', name="my_article")
        articles = response.json()
        
        if not articles:
            return
        
        if isExecute(20):
            article = random.choice(articles)
            self.client.put('/user/articles/{}'.format(article['id']), {'title': 'locust_update', 'body': 'locust_update'}, name="update_article")
            
        if isExecute(10):
            article = random.choice(articles)
            self.client.delete('/user/articles/{}'.format(article['id']), name="delete_article")
            
    @task
    def unlike(self):
        
        response = self.client.get('/user/liked_articles', name="my_like_article")
        articles = response.json()
        
        if not articles:
            return
            
        if isExecute(20):
            article = random.choice(articles)
            self.client.delete('/articles/{}/unlike'.format(article['id']), name="unlike_article")
                
    
    @task
    def stop(self):
        self.interrupt()

class UserBehavior(SequentialTaskSet):
    
    wait_time = constant(1)
    
    @task
    def login(self):
        number = random.randint(1, 500000)
        name = f'dummy_{number}'
        self.client.post('/login', {'name': name, 'password': name}, None, name="login")
        
    @task
    def profile(self):
        self.client.get('/user', name="show_user")
        
    tasks = [ArticleTaskSet]
        
    @task
    def logout(self):
        self.client.post('/logout', None, None, name="logout")
        self.interrupt(False)

class DummyUser(HttpUser):
    
    tasks = [UserBehavior]
