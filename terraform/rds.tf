variable "engine" {
  type    = string
  default = "postgres"
}

variable "engine_version" {
  type    = string
  default = "11.7"
}

variable "db_name" {
  type = string
}

variable "db_user" {
  type = string
}

variable "db_pass" {
  type = string
}

resource "aws_db_instance" "load-test" {
  instance_class               = "db.m5.large"
  apply_immediately            = true
  identifier                   = "load-test"
  db_subnet_group_name         = aws_db_subnet_group.load-test.name
  engine                       = var.engine
  engine_version               = var.engine_version
  performance_insights_enabled = true
  vpc_security_group_ids       = [aws_security_group.rds.id]
  skip_final_snapshot          = true
  multi_az                     = false
  name                         = var.db_name
  username                     = var.db_user
  password                     = var.db_pass
  allocated_storage            = 10
}

resource "aws_db_subnet_group" "load-test" {
  name       = "load-test"
  subnet_ids = [for s in aws_subnet.private : s.id]
}
