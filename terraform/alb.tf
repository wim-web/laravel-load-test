resource "aws_alb" "this" {
  name               = "load-test"
  internal           = false
  load_balancer_type = "application"
  subnets            = [for p in aws_subnet.public : p.id]
  security_groups    = [aws_security_group.web.id]
  tags = {
    "Name" = "load-test"
  }
}

resource "aws_alb_listener" "http" {
  load_balancer_arn = aws_alb.this.arn
  port              = 80
  protocol          = "HTTP"
  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.web.arn
  }
}

resource "aws_alb_target_group" "web" {
  name        = "load-test-web"
  port        = 80
  protocol    = "HTTP"
  vpc_id      = aws_vpc.this.id
  target_type = "instance"
  tags = {
    "Name" = "load-test-web"
  }
}

resource "aws_alb_target_group_attachment" "web" {
  count            = length(aws_instance.web)
  target_group_arn = aws_alb_target_group.web.arn
  target_id        = aws_instance.web[count.index].id
  port             = 80
}
