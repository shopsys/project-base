# Shopsys Framework

## Important - current status
[Shopsys Framework](https://www.shopsys-framework.com/) has been developed since the beginning of 2014 and there are some projects in production that already leverage the framework. 
In March 2018 we extracted the core functionality into [separate package](https://github.com/shopsys/framework) to provide future upgradeability of projects based on the framework. 
But the extraction causes that majority of customizations is not achievable at the moment.
You can only use [standard Symfony extensibility concepts](https://symfony.com/doc/3.4/bundles/override.html), 
eg. services and templates overriding. 
We have already implemented [steps towards the project customization](docs/wip_glassbox/wip-glassbox-customization.md) 
but there is still quite an amount of work that needs to be done. 
We are also planning architectural changes, 
see the roadmap on the picture below and read more about our near-future plans in [our blog]((https://blog.shopsys.com/here-it-is-shopsys-framework-development-roadmap-154edb549c97)).

**The framework is fully-functional now, 
see the [rough functionality specifications](https://github.com/shopsys/project-base/blob/master/CHANGELOG.md#added-7). 
But we do not recommend to build production sites on the framework at the moment. 
Nevertheless, you are more than welcome to explore the source codes and play with the framework. 
We would be very happy to get any [feedback](https://github.com/shopsys/shopsys/blob/master/project-base/CONTRIBUTING.md) from you.**

![Shopsys Framework roadmap](docs/img/roadmap.png 'Shopsys Framework roadmap')

## Documentation
For documentation of Shopsys Framework itself see [Shopsys Framework Knowledge Base](docs/index.md).

Documentation of the specific project built on Shopsys Framework should be in [Project Documentation](docs/project/index.md).

## Installation
Create new project from [`shopsys/project-base`](https://github.com/shopsys/project-base) using composer.
```
composer create-project shopsys/project-base --no-install
```
For more detailed instructions, follow one of the installation guides:
- [Installation via Docker (recommended)](docs/docker/installation/installation-using-docker.md)
- [Detailed native installation](docs/introduction/installation-guide.md)

### What to do next
Open [http://127.0.0.1:8000/](http://127.0.0.1:8000/) to see running application.

You can also login into the administration section on [http://127.0.0.1:8000/admin/](http://127.0.0.1:8000/admin/) with default credentials:
* Username: `admin` or `superadmin` (the latter has access to advanced options)
* Password: `admin123`

## Contributing
Shopsys Framework is decoupled into many packages, but all sources, 
pull requests and issues are maintained in one repository - [`shopsys/shopsys`](https://github.com/shopsys/shopsys). 
That is the only place where to [contribute](CONTRIBUTING.md) when you have any feedback for us.
You can take part in making Shopsys Framework better. 

## Need help
* [Docker troubleshooting](docs/docker/docker-troubleshooting.md)
* Contact us on our Slack [http://slack.shopsys-framework.com/](http://slack.shopsys-framework.com/).