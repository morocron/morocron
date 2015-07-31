#!/bin/bash
set -u

#Init script variables
#---------------------
red='\e[0;31m'
yellow='\e[1;33m'
green='\e[1;32m'
norm='\e[0;m'
RELEASE_VERSION=$1
GIT=`which git`
MASTER_BRANCH_NAME='master'
DEVELOP_BRANCH_NAME='develop'

#Commands
#--------
GIT_PULL_COMMAND="git stash; git checkout ${MASTER_BRANCH_NAME}; git pull";

#Script start
#------------
echo -e "${yellow}Start script for merge release into master${norm}"

#Checking out on master
#----------------------
echo -e "${yellow}Checking out to ${MASTER_BRANCH_NAME} branch${norm}"
$GIT checkout ${MASTER_BRANCH_NAME}
$GIT pull
CURRENT_BRANCH=`$GIT rev-parse --abbrev-ref HEAD`

#Check correct branch and display params
#---------------------------------------
if [ "$CURRENT_BRANCH" != "${MASTER_BRANCH_NAME}" ]; then
    echo -e "${red}Not on branch '${MASTER_BRANCH_NAME}', first checkout '${MASTER_BRANCH_NAME}' branch${norm}"
    exit;
else
    echo -e "${yellow}Now on branch '${MASTER_BRANCH_NAME}'"
fi

#Merge process
#-------------
echo -e "${yellow}Start merging ${DEVELOP_BRANCH_NAME} into ${MASTER_BRANCH_NAME}${norm}"
$GIT merge ${DEVELOP_BRANCH_NAME} -m "Merge ${DEVELOP_BRANCH_NAME} into ${MASTER_BRANCH_NAME}"
if [ $? -eq 0 ]; then
    $GIT push
    $GIT tag -a ${RELEASE_VERSION} -m "Tagging ${MASTER_BRANCH_NAME} branch ${RELEASE_VERSION}" && git push --tags
    echo -e "${green}SUCCES : Merge release ${DEVELOP_BRANCH_NAME} into ${MASTER_BRANCH_NAME} succedded${norm}"
else
    echo -e "${red}FAILED : Merge ${DEVELOP_BRANCH_NAME} into ${MASTER_BRANCH_NAME} failed"
    echo -e "Please verify the merge. An error occured.${norm}"
    exit;
fi

#Back to develop branch
#----------------------
echo -e "${yellow}Back to develop branch${norm}"
$GIT checkout develop
$GIT pull