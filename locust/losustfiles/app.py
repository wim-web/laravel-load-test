from locust import HttpUser, constant, task, tag

class App(HttpUser):
    
    @tag('health_check')
    @task
    def health_check(self):
        self.client.get('/health_check', name="health_check")
        
    @tag('show_articles')
    @task
    def show_articles(self):
        self.client.get('/articles?page=1000', name="show_article")
        
    @tag('store_article')
    @task
    def store_article(self):
        self.client.post('/articles', {'title': 'locust', 'body': 'locust'}, None, name="store_article")
        