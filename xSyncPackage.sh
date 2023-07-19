#!/bin/bash

#find absolute path of this file, and cd to location dir
SOURCE="${BASH_SOURCE[0]}"
DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
cd $DIR

composer archive --format=zip --file=yhighlight

exit
