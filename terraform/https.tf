variable "acm_domain" {
  type = string
}

# あらかじめ東京リージョンで証明書を作成しておいてください
data "aws_acm_certificate" "load-test" {
  domain = var.acm_domain
}

resource "aws_alb_listener" "https" {
  load_balancer_arn = aws_alb.this.arn
  port              = 443
  protocol          = "HTTPS"
  certificate_arn   = data.aws_acm_certificate.load-test.arn
  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.web.arn
  }
}
