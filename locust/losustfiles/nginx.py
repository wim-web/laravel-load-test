from locust import HttpUser, task, constant

class Nginx(HttpUser):
    
    wait_time = constant(1)
    
    @task
    def getStaticFile(self):
        self.client.get('/status')