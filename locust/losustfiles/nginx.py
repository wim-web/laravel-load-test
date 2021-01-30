from locust import HttpUser, task, constant

class Nginx(HttpUser):
    
    @task
    def getStaticFile(self):
        self.client.get('/status.txt', name="status")