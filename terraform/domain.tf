variable "domain" {
  type = string
}

resource "aws_route53_zone" "load_test" {
  name = var.domain
}
