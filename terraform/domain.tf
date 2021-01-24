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

resource "aws_route53_zone" "private" {
  name = "load-test"
  vpc {
    vpc_id = aws_vpc.this.id
  }
}

resource "aws_route53_record" "rds" {
  zone_id = aws_route53_zone.private.zone_id
  name    = "rds.${aws_route53_zone.private.name}"
  type    = "CNAME"
  ttl     = 300
  records = [aws_db_instance.load-test.address]
}
