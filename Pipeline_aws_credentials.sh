mkdir -p deploy/artifacts/keys
echo """access_key_id: $AWS_ACCESS_KEY
secret_access_key: $AWS_SECRET
region: ap-southeast-2""" > deploy/artifacts/keys/aws.yml

cat deploy/artifacts/keys/aws.yml