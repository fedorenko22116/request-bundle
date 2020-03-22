#!/bin/sh

until nc -z -v -w30 app 9000
do
    echo "Waiting for application connection..."
    sleep 5
done

sleep 5

host=${TEST_HOST:-'http://127.0.0.1:8000'}

###############################################################################

url="$host/attributes/123?bar=1&test_id=1"
expected='{"foo":"123","bar":"1","entityText":"Foo text"}'
result="$(curl -s $url)"

if [ $result = $expected ];
 then
   echo 'Attributes tests passed OK!';
 else
   echo "Unexpected output $result. Expected $expected"
   exit 1;
fi

###############################################################################

url="$host/query?foo=123&bar_baz=1"
expected='{"foo":"123","barBaz":true,"dto":"SomeAwesomeText"}'
result="$(curl -s $url)"

if [ $result = $expected ];
 then
   echo 'Query tests passed OK!';
 else
   echo "Unexpected output $result. Expected $expected"
   exit 1;
fi

###############################################################################

url="$host/body"
expected='{"foo":"123","barBaz":true,"dto":"SomeAwesomeText"}'
result="$(curl -s $url -d 'foo=123&bar_baz=1')"

if [ $result = $expected ];
 then
   echo 'Body tests passed OK!';
 else
   echo "Unexpected output $result. Expected $expected"
   exit 1;
fi


