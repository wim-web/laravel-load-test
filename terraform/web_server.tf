resource "aws_instance" "web" {
  count                       = 1
  instance_type               = "m5.large"
  ami                         = data.aws_ami.web.id
  associate_public_ip_address = true
  subnet_id                   = aws_subnet.public[keys(local.az)[count.index % length(local.az)]].id
  vpc_security_group_ids      = [aws_security_group.web.id]
  iam_instance_profile        = aws_iam_instance_profile.ec2.id
  tags = {
    "Name" = "load-test-web"
  }
}

data "aws_caller_identity" "self" {}

data "aws_ami" "web" {
  most_recent = true
  name_regex  = "^load-test_"
  owners      = [data.aws_caller_identity.self.account_id]
}

resource "aws_iam_instance_profile" "ec2" {
  name = "ec2"
  role = aws_iam_role.ec2_for_ssm.name
}

resource "aws_iam_role" "ec2_for_ssm" {
  name               = "EC2ForSSM"
  assume_role_policy = file("./policy/ec2_assume_role.json")
}

resource "aws_iam_role_policy_attachment" "ec2_for_ssm" {
  role       = aws_iam_role.ec2_for_ssm.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonEC2RoleforSSM"
}
