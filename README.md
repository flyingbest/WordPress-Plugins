# WordPress-Plugins

### Requirements

- PHP 5.4 programming skill. Wordpress is PHP application. function, callback, class. (at least)
- mysql 5.6+ (also know about WordPress DataBase), and query instructions.
- Client-Side language : HTML, Javascript, CSS.

### PHP concepts

PHP programming concepts: function, class

```
function f(x) = x^2
function g(x) = x+1
```

**input, output, function(processing)**

```php
function f($x) { return $x * $x; }
function g($x) { return $x + 1; }
```

(option) type hinting is available.

### advanced : class

Same as CPP concepts. Class. (inheritance, object...)

### Callback Function

Plugins extend WordPress. threr is a 'protocol'. **Imagine the protocol.** Wordpress core knows every possible situation. I throw it at this time to do something. Then, Core is executed in accordance with To-Do List.

In practice examples :

```
function(){
	echo
}  // this is callback function.
```

> A callback is any executable code that is passed as an argument to other code, which is expected to call back (execute) the argument at a given time. This execution may be immediate as in a synchronous callback, or it might happen at a later time as in an asynchronous callback. 
> `- Referenced by wikipedia.  

### Environments

rocketeer 5G 192.168.1.22
<!-- wordpress / 1 -->
taeyoon-hello-world.php

### GOAL!

- Plan your new plugin idea.


