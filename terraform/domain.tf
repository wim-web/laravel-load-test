variable "domain" {
  type = string
}

resource "aws_route53_zone" "load_test" {
  name = var.domain
}

resource "aws_route53_record" "alb" {
  zone_id = aws_route53_zone.load_test.zone_id
  name    = "alb.${var.domain}"
  type    = "A"
  alias {
    name                   = aws_alb.this.dns_name
    zone_id                = aws_alb.this.zone_id
    evaluate_target_health = false
  }
}
