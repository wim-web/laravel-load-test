from locust import HttpUser, constant, task, tag

def my_task(user):
    user.client.get('/')

class My(HttpUser):
    
    wait_time = constant(1)
    
    @tag('health_check')
    @task
    def health_check(self):
        self.client.get('/health_check')
        
    @tag('show_articles')
    @task
    def show_articles(self):
        self.client.get('/articles?page=1000')
        
    @tag('store_article')
    @task
    def store_article(self):
        self.client.post('/articles', {'title': 'locust', 'body': 'locust'})
        