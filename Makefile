# Default Global variables
PROJECT = schedullo

PWD = $(shell pwd)
VERSION = $(shell git rev-parse --short HEAD)
ARTIFACTS_DIR = $(PWD)/deploy/artifacts

# Package the application
#
# make package VERSION=abc123
package: clean
		mkdir -p $(ARTIFACTS_DIR) && \
			zip -r --exclude=*.git* --exclude=*artifacts* --exclude=*beanstalk* --exclude=*deploy* $(ARTIFACTS_DIR)/schedullo-$(VERSION).zip . && \
			chmod -R 777 $(ARTIFACTS_DIR)
.PHONY: package

clean:
	rm -Rf $(ARTIFACTS_DIR)
.PHONY: clean



# DEPLOYMENT - Global (default) variables
ENVIRONMENT = dev
TERMINATE_SAFELY = true
DEPLOY_STRAT = deploy:standard
OUT_DIR = $(ARTIFACTS_DIR)
TASK_RUNNER = ./node_modules/.bin/gulp
TERMINATE_SAFELY = true
AWS_KEY_FILE = $(ARTIFACTS_DIR)/keys/aws.yml
BEANSTALK_CONFIG_FILE = artifacts/beanstalk/$(ENVIRONMENT).json
ZIP_FILE = $(ARTIFACTS_DIR)/schedullo-$(VERSION).zip
AMI_STACK = 64bit Amazon Linux 2017.03 v2.4.1 running PHP 5.6

# Create AWS Keys

keys:
	mkdir -p $(ARTIFACTS_DIR)/keys && \
	echo """access_key_id: $AWS_ACCESS_KEY
		secret_access_key: $AWS_SECRET
		region: ap-southeast-2""" > $(AWS_KEY_FILE)
	cat $(AWS_KEY_FILE)

.PHONY: keys


# Deploy our application to AWS
#
# make deploy VERSION=a3id8s9s
# make deploy PROJECT=service-api ARTIFACT=artifacts/whatever.zip OUT_DIR=articfacts AWS_KEY_FILE=keys/aws-prod.yml ENVIRONMENT=prod/prev BEANSTALK_CONFIG_FILE=artifacts/beanstalk/prod.json AMI_STACK="whatever AMI"
deploy: $(ZIP_FILE) $(AWS_KEY_FILE)
	cp -R -v $(PWD)/beanstalk $(ARTIFACTS_DIR)/ && \
	cd deploy && \
	ls -l && \
	ls -l artifacts/ && \
	ls -l artifacts/beanstalk/ && \
	$(TASK_RUNNER) $(DEPLOY_STRAT) \
		--elasticBeanstalkAppName=$(PROJECT) \
		--amiStack="${AMI_STACK}" \
		--awsKeysFilePath=$(AWS_KEY_FILE) \
		--archiveFilePath=$(ZIP_FILE) \
		--environmentType=$(ENVIRONMENT) \
		--beanstalkConfigFilePath=$(BEANSTALK_CONFIG_FILE) \
		--outputPath=$(OUT_DIR)
	chmod -R 755 $(OUT_DIR)/env.*
.PHONY: deploy

# Perform a cname swap
#
# make cnameSwap TARGET_CNAME=jumpin-prod SOURCE_CNAME=jumpin-prod-xxxx AWS_KEY_FILE=keys/aws-prod.yml ENVIRONMENT=prod
cnameSwap: $(AWS_KEY_FILE)
	$(TASK_RUNNER) deploy:swapEnvironmentCnames \
		--elasticBeanstalkAppName=$(PROJECT) \
		--awsKeysFilePath=$(AWS_KEY_FILE) \
		--fromOldEnvCName=$(SOURCE_CNAME) \
		--toNewEnvCName=$(TARGET_CNAME) \
		--environmentType=$(ENVIRONMENT)
.PHONY: cnameSwap

# Terminate an beanstalk AWS environment from an application
#
# Example:
#   make terminateEnv ENVIRONMENT_NAME=tv-srv-api-aaa.elb.com AWS_KEY_FILE=keys/aws-prod.yml
terminateEnv: $(AWS_KEY_FILE)
	$(TASK_RUNNER) deploy:terminateEnvironment \
		--elasticBeanstalkAppName=$(PROJECT) \
		--awsKeysFilePath=$(AWS_KEY_FILE) \
		--terminateSafely=$(TERMINATE_SAFELY) \
		--environmentName=$(ENVIRONMENT_NAME)
.PHONY: terminateEnv

# Terminate an beanstalk AWS environment from an application
#
# Example:
#   make terminateOldEnv AWS_KEY_FILE=keys/aws-prod.yml ENVIRONMENT=prod
terminateOldEnv: $(AWS_KEY_FILE)
	$(TASK_RUNNER) deploy:terminateOldEnvironment \
		--elasticBeanstalkAppName=$(PROJECT) \
		--awsKeysFilePath=$(AWS_KEY_FILE) \
		--environmentType=$(ENVIRONMENT)
.PHONY: terminateOldEnv
