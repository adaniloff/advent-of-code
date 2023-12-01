# Advent of code 2023

## Installation

### just.make

This project is using **just.make** to make developers experience easier.
You can use the library:
- through a real desktop environment (see [Use globally](#use-globally))
- through the docker image (see [Use with docker](#use-with-docker))

#### 1. Easiest way: use "just" globally

> Check [the documentation](https://just.systems/man/en/chapter_4.html) to install the library on your env.  
> For **PopOS** usage, it should be `sudo apt install just`.

Just execute `just` in your terminal to display the help.

###### How to init the project

After having `just` installed, **run** `just init` !

#### 2. Lighest way: use "just" within docker

> No installation needed !

You have 2 ways of running a container:

- **exec** mode: `docker-compose exec app just`
- **run --rm** mode: `docker-compose run --rm app just`

###### How to init the project

The setup is *slightly* more complicated than above:
- **build the image**: `$(cat justfile| grep "@docker-compose -f" | grep "up -d" | sed 's/@//g' | sed 's/up -d//g') build`
- **initialize the container** with `$(cat justfile| grep "@docker-compose -f" | grep "up -d" | sed 's/@//g' | sed 's/up -d//g') run --rm app touch .doit`
- **start the container** with `$(cat justfile| grep "@docker-compose -f" | grep "up -d" | sed 's/@//g' | sed 's/up -d//g') up -d`
- **rm the .doit file** with `$(cat justfile| grep "@docker-compose -f" | grep "up -d" | sed 's/@//g' | sed 's/up -d//g') run --rm app rm .doit`
- enjoy !

#### Resources
- Project: https://just.systems/
- IDE Plugins:
  - Jetbrains > https://plugins.jetbrains.com/plugin/18658-just
  - VSCode > https://github.com/sclu1034/vscode-just
- Documentation: https://just.systems/man/en/
- Cheat sheet: https://cheatography.com/linux-china/cheat-sheets/justfile/

## Documentation

The main project is under the folder **api**, which divide the code under **3 subdirectories**:
- **dev** *> which contains development tools* ([read the doc](./api/dev/README.md))
- **packages** *> which contains internal shareable tools (sdk-like but easier to maintain)* ([read the doc](./api/packages/README.md)) (**disclaimer**: *it is important to note that this directory will probably be removed later on, the code will be placed under **src**)*
- **src** *> which contains the project code*

## Challenges

#### Day 1

The file containing the data used by the project is located in `api/src/Datasets/Day1/day1.txt`.
Run the command `just sfc app:day1:compute` to retrieve the result, according to this dataset.
