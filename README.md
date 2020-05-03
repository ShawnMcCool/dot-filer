# Dot-Filer

Dot Filer is a PHP dot file management tool built to provide a no-nonsense approach to a common scenario.

- [Overview](#overview)
    - [Why Dot Files?](#why-dot-files)
    - [The Backup Model](#the-backup-model)
    - [The Centralized Repository](#the-centralized-repository)
    - [Target Files](#target-files)
- [Procedures](#procedures)
    - [Overview](#overview)
    - [Backup](#backup)
    - [Restore](#restore)
- [Development](#development)
    - [Maintainers](#maintainers)
    - [Testing](#testing)
    
## Overview

### Why dot files?

When a file or folder name is preceded with a dot "." it's considered a hidden file by most operating systems. This is merely a convenience that allows configuration and other files to not clutter up simple directory listings.

When applying configuration changes to a Linux installation, it's typical to work with files both in the user's home directory and other directories such as /etc.

Not all important configuration files and folders begin with a dot. However they've come to be included in the umbrella term. 

Many users put much effort into the configuration of their systems and much of this configuration lies within these text files.

By backing these files up and versioning them we can prevent the loss of a lot of hard work and make it easier to deploy these configurations to other machines.

### The Backup Model

This management model has 2 pieces. The first is the "centralized repository" and the second is off-site storage.

Dot-Filer is a tool for managing the centralized repository. This leaves it up to you to manage off-site storage. Committing to a git repository and pushing the changes offsite is an optimal strategy.

### The Centralized Repository

In order to make storage simple, dot filer moves all interesting directories and files to the centralized repository (a directory) and then creates a symlink such that the files and directorys are accessible at their original location. Moving files, making sure the destination location is set up, and managing symlinks are all part of the Dot-Filer model.

A "target file" is a file that contains a list of files and directories that you want to back up each delimited by a new-line. 

These target files are necessary for all Dot-Filer operations because they provide necessary context. This means that it's possible to have many configurations per machine to manage many repositories.

### Target Files

Your repo can contain many target files. They're used as a command line argument for backup and restore commands. For example, it's entirely possible that you have a master computer that you want to back up configurations from. Then you may want to distribute these configurations to many machines.. but not ALL configurations. It's possible to have a large target file for backup and small target files for restoration of only a few configurations.

## Procedures

### Overview

The overview will show you the status of each path in the target file as it relates to backup and restore operations.

```shell script
$ dotfiler overview <target-file> <repo-path>
```

### Backup

The backup procedure processes each target from the target file. If it finds one that is not already managed then it will move the target to the repository and create a symlink at its original position.

```shell script
$ dotfiler backup <target-file> <repo-path>
```

### Restore

During restoration any target file that does not exist or does not exist as a symlink to the target's repo path will be destroyed and a new symlink that points to the target's repo path will take its place.

```shell script
$ dotfiler restore <target-file> <repo-path>
```

## Development

### Maintainers

This packages was written and is maintained by Shawn McCool.

### Testing
 
I'll fill this out more later.

Run tests

```shell script
$ composer install
$ ./test
```
