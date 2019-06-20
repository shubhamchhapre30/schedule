import gulp from 'gulp';
import fs from 'fs';
import path from 'path';
import yaml from 'js-yaml';
import gutil from 'gulp-util';
import { argv } from 'yargs';
import DeployUtils from 'aws-deploy-utils';

// Apparently alarms are not used anymore, Liam is not aware of it
function getAlarmsConfiguration() {
  return [];
}

function getAwsCredentials(keyFile) {
  return yaml.safeLoad(fs.readFileSync(keyFile, 'utf8'));
}

function getDeployUtilsInfo(beanstalkConfigFilePath, outputPath) {
  return {
    beanstalkConfig: require(`./${beanstalkConfigFilePath}`),
    outputPath: path.resolve(path.join(outputPath, 'env.json')),
  };
}

function getDeployUtils() {
  const awsCredentials = getAwsCredentials(argv.awsKeysFilePath);
  return new DeployUtils({
    elasticBeanstalkAppName: argv.elasticBeanstalkAppName,
    awsAccessKeyId: process.env.AWS_ACCESS_KEY_ID || awsCredentials.access_key_id,
    awsSecretAccessKey: process.env.AWS_SECRET_ACCESS_KEY || awsCredentials.secret_access_key,
    awsSessionToken: process.env.AWS_SESSION_TOKEN || awsCredentials.session_token || null,
    awsRegion: awsCredentials.region || 'ap-southeast-2',
  });
}

// gulp deploy:standard
//   --elasticBeanstalkAppName=tv-srv-api
//   --awsKeysFilePath=keys/aws-prod.yml
//   --archiveFilePath=artifacts/tv-srv-api-maxime.aoustin.zip
//   --environmentType=test
//   --beanstalkConfigFilePath=config/beanstalk/test.json
//   --outputPath=artifacts
gulp.task('deploy:standard', function(done) {
  const deployUtilsInfo = getDeployUtilsInfo(argv.beanstalkConfigFilePath, argv.outputPath);
  const deployUtils = getDeployUtils();

  deployUtils.deploy({
    outputPath: deployUtilsInfo.outputPath,
    beanstalkConfig: deployUtilsInfo.beanstalkConfig,
    environmentType: argv.environmentType,
    archiveFilePath: argv.archiveFilePath,
    awsStackName: argv.amiStack,
  }).then(function(response) {
    gutil.log(gutil.colors.green('Standard Deployment is successful'));
    gutil.log(gutil.colors.green(response));
    done();
  }).catch(function(error) {
    gutil.log(gutil.colors.red('Standard Deployment has failed'));
    gutil.log(gutil.colors.red(error));
    done(error);
  });
});

// gulp deploy:bluegreen
//   --elasticBeanstalkAppName=tv-srv-api
//   --awsKeysFilePath=keys/aws-prod.yml
//   --archiveFilePath=artifacts/tv-srv-api-maxime.aoustin.zip
//   --environmentType=prev
//   --beanstalkConfigFilePath=config/beanstalk/prev.json
//   --outputPath=artifacts
gulp.task('deploy:bluegreen', function(done) {
  // Build the deploy object
  const deployUtilsInfo = getDeployUtilsInfo(argv.beanstalkConfigFilePath, argv.outputPath);
  const deployUtils = getDeployUtils();

  deployUtils.deployBlueGreen({
    outputPath: deployUtilsInfo.outputPath,
    beanstalkConfig: deployUtilsInfo.beanstalkConfig,
    environmentType: argv.environmentType,
    archiveFilePath: argv.archiveFilePath,
    awsStackName: argv.amiStack,
    alarmsConfiguration: getAlarmsConfiguration(argv.environmentType),
  }).then(function(response) {
    gutil.log(gutil.colors.green('Blue Green Deployment is successful'));
    gutil.log(gutil.colors.green(response));
    done();
  }).catch(function(error) {
    gutil.log(gutil.colors.red('Blue Green Deployment has failed'));
    gutil.log(gutil.colors.red(error));
    done(error);
  });
});

// gulp deploy:newUniqueEnvironment
//   --elasticBeanstalkAppName=tv-srv-api
//   --awsKeysFilePath=keys/aws-prod.yml
//   --archiveFilePath=artifacts/tv-srv-api-maxime.aoustin.zip
//   --environmentType=prod
//   --beanstalkConfigFilePath=config/beanstalk/prod.json
//   --outputPath=artifacts
gulp.task('deploy:newUniqueEnvironment', function(done) {
  const deployUtilsInfo = getDeployUtilsInfo(argv.beanstalkConfigFilePath, argv.outputPath);
  const deployUtils = getDeployUtils();

  deployUtils.deployUniqueEnvironment({
    outputPath: deployUtilsInfo.outputPath,
    beanstalkConfig: deployUtilsInfo.beanstalkConfig,
    environmentType: argv.environmentType,
    archiveFilePath: argv.archiveFilePath,
    awsStackName: argv.amiStack,
  }).then(function(response) {
    gutil.log(gutil.colors.green('Blue Green Deployment is successful'));
    gutil.log(gutil.colors.green(response));
    done();
  }).catch(function(error) {
    gutil.log(gutil.colors.red('New Deployment has failed'));
    gutil.log(gutil.colors.red(error));
    done(error);
  });
});

// gulp deploy:swapEnvironmentCnames
//   --elasticBeanstalkAppName=tv-srv-api
//   --awsKeysFilePath=keys/aws-prod.yml
//   --fromOldEnvCName=tv-srv-api-prod
//   --toNewEnvCName=tv-srv-api-prod-abcd1
//   --environmentType=prod
gulp.task('deploy:swapEnvironmentCnames', function(done) {
  const deployUtils = getDeployUtils();

  deployUtils.swapEnvironmentCnamesByCname({
    fromOldEnvCName: argv.fromOldEnvCName,
    toNewEnvCName: argv.toNewEnvCName,
    alarmsConfiguration: getAlarmsConfiguration(argv.environmentType),
  }).then(function(response) {
    gutil.log(gutil.colors.green('Swapping CNAMEs is successful'));
    gutil.log(gutil.colors.green(response));
    done();
  }).catch(function(error) {
    gutil.log(gutil.colors.green('Swapping CNAMEs has failed'));
    gutil.log(gutil.colors.red(error));
    done(error);
  });
});

// gulp deploy:terminateOldEnvironment
//   --elasticBeanstalkAppName=tv-srv-api
//   --awsKeysFilePath=keys/aws-prod.yml
//   --environmentType=prod
gulp.task('deploy:terminateOldEnvironment', function(done) {
  const deployUtils = getDeployUtils();

  deployUtils.terminateOldEnviroments({
    environmentType: argv.environmentType,
  }).then(function(response) {
    gutil.log(gutil.colors.green('Terminated Old Environments is successful'));
    gutil.log(gutil.colors.green(response));
    done();
  }).catch(function(error) {
    gutil.log(gutil.colors.green('Terminated Old Environments has failed'));
    gutil.log(gutil.colors.red(error));
    done(error);
  });
});

// gulp deploy:terminateEnvironment
//   --elasticBeanstalkAppName=tv-srv-api
//   --awsKeysFilePath=keys/aws-prod.yml
//   --environmentName=tv-srv-api-abcd2
//   --terminateSafely=true[|false]
gulp.task('deploy:terminateEnvironment', function(done) {
  let safeTermination = true;
  const deployUtils = getDeployUtils();

  if (argv.terminateSafely === 'false') {
    safeTermination = false;
  }

  deployUtils.terminateEnvironment({
    environmentName: argv.environmentName,
    safely: safeTermination,
  }).then(function(response) {
    gutil.log(gutil.colors.green('Terminated Environment is successful'));
    gutil.log(gutil.colors.green(`${response.EnvironmentName} is currently ${response.Status}`));
    done();
  }).catch(function(error) {
    gutil.log(gutil.colors.green('Terminated Environment has failed'));
    gutil.log(gutil.colors.red(error));
    done(error);
  });
});
