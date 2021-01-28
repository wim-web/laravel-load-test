from locust import HttpUser, TaskSet, task, constant, SequentialTaskSet
import random

def isExecute(percentage) -> bool:
    return random.random() <= (percentage / 100)

class ArticleTaskSet(SequentialTaskSet):
    
    def __init__(self, parent):
        super().__init__(parent)
    
    @task
    def wandering(self):
        page = random.randint(1, 5000)
        for _ in range(random.randint(1, 10)):
            
            page += 1
            self.client.get('/articles?page={}'.format(page))
            
            if isExecute(50):
                articleID = random.randint(1, 100) * page
                self.client.get('articles/{}'.format(articleID))
                
                if isExecute(10):
                    self.client.post('/user/articles/{}/like'.format(articleID))
    
    @task
    def create(self):
        if isExecute(70):
            self.client.post('/articles', {'title': 'locust', 'body': 'locust'})
            
    @task
    def my_article(self):
        
        response = self.client.get('/user/articles')
        articles = response.json()
        
        if not articles:
            return
        
        if isExecute(20):
            article = random.choice(articles)
            self.client.put('/user/articles/{}'.format(article['id']), {'title': 'locust_update', 'body': 'locust_update'})
            
        if isExecute(10):
            article = random.choice(articles)
            self.client.delete('/user/articles/{}'.format(article['id']))
            
    @task
    def unlike(self):
        
        response = self.client.get('/user/liked_articles')
        articles = response.json()
        
        if not articles:
            return
            
        if isExecute(5):
            article = random.choice(articles)
            self.client.delete('/articles/{}/unlike'.format(article['id']))
                
    
    @task
    def stop(self):
        self.interrupt(False)

class UserBehavior(SequentialTaskSet):
    
    wait_time = constant(1)
    
    @task
    def login(self):
        number = random.randint(1, 500000)
        name = f'dummy_{number}'
        self.client.post('/login', {'name': name, 'password': name})
        
    @task
    def profile(self):
        self.client.get('/user')
        
    tasks = [ArticleTaskSet]
        
    @task
    def logout(self):
        self.client.post('/logout')
        self.interrupt(False)

    
    
    
