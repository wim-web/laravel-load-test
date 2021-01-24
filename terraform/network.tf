locals {
  az = {
    ap-northeast-1a = 1
    ap-northeast-1c = 2
    ap-northeast-1d = 3
  }
}


resource "aws_vpc" "this" {
  cidr_block           = "10.114.0.0/16"
  enable_dns_support   = true
  enable_dns_hostnames = true
  tags = {
    "Name" = "load_test"
  }
}

resource "aws_internet_gateway" "this" {
  vpc_id = aws_vpc.this.id
  tags = {
    "Name" = "load_test"
  }
}

resource "aws_subnet" "public" {
  for_each          = local.az
  vpc_id            = aws_vpc.this.id
  cidr_block        = cidrsubnet(aws_vpc.this.cidr_block, 8, each.value)
  availability_zone = each.key
  tags = {
    "Name" = "load-test_public-${substr(each.key, -2, 2)}"
  }
}

resource "aws_route_table" "public" {
  vpc_id = aws_vpc.this.id
  tags = {
    "Name" = "public"
  }
}

resource "aws_route" "public" {
  route_table_id         = aws_route_table.public.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.this.id
}

resource "aws_route_table_association" "public" {
  for_each       = aws_subnet.public
  subnet_id      = each.value.id
  route_table_id = aws_route_table.public.id
}

resource "aws_subnet" "private" {
  for_each          = local.az
  vpc_id            = aws_vpc.this.id
  cidr_block        = cidrsubnet(aws_vpc.this.cidr_block, 8, each.value + 100)
  availability_zone = each.key
  tags = {
    "Name" = "load-test_private-${substr(each.key, -2, 2)}"
  }
}
