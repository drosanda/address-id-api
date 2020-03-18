# Multitail Configuration
This is walkhtrough for viewing seme.log using multitail and its color scheme

## Install multitail
Multitail can be installed through Ubuntu or RHEL.

### Ubuntu
For installing multitail for ubuntu machine
```
$ sudo apt-get update
$ sudo apt-get install multitail
```

### Centos / RHEL
To get MultiTail on Red Hat based distributions, you must turn on EPEL repository and then run the following command on the terminal to install it.
```
# yum install -y multitail
```

## Multitail configuration files
Create file on home directory name .multitailrc and then pasted this:

```
colorscheme:seme_log
cs_re:white,red:PHP (Stack trace:|Fatal error:).*
mcsre_s:,,bold:PHP Fatal error: (.*)
# stack traces from xdebug have leading "PHP +"
cs_re:white,red:PHP [ 0-9]+\.
cs_re:white,yellow:PHP Warning: .*
mcsre_s:,,bold:PHP Warning: (.*)
cs_re:yellow:PHP Notice: .*
mcsre_s:,,bold:PHP Notice: (.*)

# custom severity tags
cs_re:white,red,bold:\[(ALERT|CRIT|EMERG|FATAL)\]
cs_re:red:\[(ERROR)\]
cs_re:yellow:\[(NOTICE|WARN)\]
cs_re:cyan:\[(INFO|DEBUG)\]
mcsre_s:,,bold:\[(ALERT|CRIT|EMERG|ERROR|NOTICE|WARN)\]

# additional substring color
# date/times
cs_re_s:cyan:^(([0-9]{2,})+ ?)+
# key-value pairs
cs_re_s:green:([a-zA-Z0-9_]+=[^ ]*)
# brackets, quotations
cs_re_s:blue:(\[|\]|\{|\})
mcsre_s:green:("[^"]*")
mcsre_s:green:('[^']*')
# file/class paths
mcsre_s:blue:(([a-zA-Z0-9_:]+(/|\\)+)+[a-zA-Z0-9_.]+)
cs_re:white,,bold:([^- ]*)::.([^\ ]+)
cs_re:yellow:--.([^\ ]+)
cs_re:yellow:__. *([^\ ]+)
cs_re:white,,bold:call.([^\ ]+)
cs_re:green,,bold:value: 1
cs_re:red,,bold:value: 0
cs_re:magenta:setting_notification_buyer
cs_re:cyan:setting_notification_seller
cs_re:red,,bold:POST:
cs_re:cyan,,blink:->
cs_re:red,,blink:MAGIC_LINK
```

## Viewing Log
For viewing log, go to root directory of application just execute command:
```
multitail -cS seme_log -i seme.log
```
