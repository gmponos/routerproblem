# Test project

This is a test project that it is created in order to explain a problem faced with symfony routes and exception handling.

### General description of my issue.

What I wanted to achieve is when a customer/user visits a path that does not exist to show a custom template. 
Inside this template we have various `path` functions of twig pointing to our home page.

Furthermore I need the customer/user to not redirect from the page he visited to another one and also return a 200 status code. As I have said we need to see a custom template here.

Please don't focus why do we have these requirements.

### Steps I followed.

- Installed a project according to documentation of symfony.

- Inside file `annotations` of config/routes path I have enabled three prefixes `en`, `el`, `fr`
- I have run the command `php bin/console debug:route` command. This is the result:

```
 -------------------------- -------- -------- ------ ----------------------------------- 
  Name                       Method   Scheme   Host   Path                               
 -------------------------- -------- -------- ------ ----------------------------------- 
  my.en                      ANY      ANY      ANY    /my                                
  my.el                      ANY      ANY      ANY    /el/my                             
  my.fr                      ANY      ANY      ANY    /fr/my                             
  my_other.en                ANY      ANY      ANY    /my/other                          
  my_other.el                ANY      ANY      ANY    /el/my/other                       
  my_other.fr                ANY      ANY      ANY    /fr/my/other                       
.... more routes
 -------------------------- -------- -------- ------ ----------------------------------- 
```

As you will notice all the routes have the suffix.

- I created two controllers `MyController`, `MyOtherController` with their views.
- Inside the view of each controller I added the `path()` function pointing back to the other controller path.
The path added inside the function is without the suffix. eg `path('my_other')`
- I have visited the pages and everything works fine.
- Afterwards I created a template `404.html.twig` and inside this template the following `{{ path('my_other') }}`
- I created a `App\Listener\CustomExceptionListener`.
- I registered this event inside the services.yaml


```
    App\Listener\CustomExceptionListener:
        class: App\Listener\CustomExceptionListener
        tags:
            - { name: kernel.event_listener, event: kernel.exception, method: onKernelException, priority: -70 }
```

- I have run the `php bin/console debug:event` this is the result:

```
"kernel.exception" event
------------------------

 ------- ------------------------------------------------------------------------------------ ---------- 
  Order   Callable                                                                             Priority  
 ------- ------------------------------------------------------------------------------------ ---------- 
  #1      Symfony\Component\HttpKernel\EventListener\ProfilerListener::onKernelException()     0         
  #2      Symfony\Bundle\SwiftmailerBundle\EventListener\EmailSenderListener::onException()    0         
  #3      Symfony\Component\HttpKernel\EventListener\ExceptionListener::logKernelException()   0         
  #4      Symfony\Component\HttpKernel\EventListener\RouterListener::onKernelException()       -64       
  #5      App\Listener\CustomExceptionListener::onKernelException()                            -70       
  #6      Symfony\Component\HttpKernel\EventListener\ExceptionListener::onKernelException()    -128 
```

- At last I finished a path that does not exist. What I get is:

`Unable to generate a URL for the named route "my_other" as such route does not exist.`

### Some more info trying to debug

- Inside the Listener I passed the router object and I have dumped it. I can see that it's collection of routes is empty.
- When I dump the request I can see that the request has only the default locale and no attributes set.
- I have set to the request the attribute `_locale` to en hardcoded but still didn't work.
- On the kernel.exception event I have also tried to set the priority of my CustomExceptionListener to -50 and still the same error.