To-Do
=====

Brainstorming
-------------

* External route configuration (YAML? PHP?)
* Routing only if there's no real resource (file, symlink or directory) with the requested name
* Server module provides standard actions only
    * Find & list objects
    * Single object display
* Plugin-based?
    * Additional modules add additional routes & handlers (e.g. Micropub)
* Support for multiple repositories
    * URLs must include repository identifier then
* [Define route defaults](https://github.com/auraphp/Aura.Router/blob/3.x/docs/defining-routes.md#default-map-route-specifications)
    * HTML & JSON Accept headers
    * Apparat base URL
* [Define a base path](https://github.com/auraphp/Aura.Router/blob/3.x/docs/other-topics.md#base-path)
* Central link generation service
* Request dispatching (URL → Response)
    * **Static resource match**: The request URL matches a real existing resource (e.g. `/downloads/catalog.pdf`)
        * File (also symlinked): Gets downloaded to the client, no magic here.
        * Directory: E.g. directory index (depends on the web server and its configuration).
    * **Alias match**: The request URL matches an arbitrary alias (e.g. `/contact`). There's no conclusive interrelation between the alias and the page content.
    * **Pattern match**: The request URL matches a particular pattern and resolves to a list of well known parameters (e.g. `/2016/05/28/1-article/1`). The parameter values control the content of the page, which is generated in a rule-based way.
* Both alias and pattern matches are dispatched to distinct Actions, following an [ADR approach](https://github.com/pmjones/adr)
