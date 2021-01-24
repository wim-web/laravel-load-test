variable "redis_version" {
  type    = string
  default = "5.0.5"
}

resource "aws_elasticache_cluster" "load-test" {
  cluster_id         = "load-test"
  engine_version     = var.redis_version
  engine             = "redis"
  node_type          = "cache.m5.large"
  num_cache_nodes    = 1
  port               = 6379
  subnet_group_name  = aws_elasticache_subnet_group.load-test.name
  security_group_ids = [aws_security_group.redis.id]
}

resource "aws_elasticache_subnet_group" "load-test" {
  name       = "load-test"
  subnet_ids = [for s in aws_subnet.private : s.id]
}
