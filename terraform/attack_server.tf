resource "aws_instance" "locust" {
  count                       = 1
  instance_type               = "t2.small"
  ami                         = data.aws_ami.locust.id
  associate_public_ip_address = true
  subnet_id                   = aws_subnet.public[keys(local.az)[count.index % length(local.az)]].id
  vpc_security_group_ids      = [aws_security_group.web.id, aws_security_group.locust.id]
  iam_instance_profile        = aws_iam_instance_profile.ec2.id
  tags = {
    "Name" = "locust"
  }
}

data "aws_ami" "locust" {
  most_recent = true
  name_regex  = "^attack_"
  owners      = [data.aws_caller_identity.self.account_id]
}
