# Dot-Filer

Dot Filer is a PHP dot file management tool built to provide a no-nonsense approach to a common scenario.

## Development Todo

- validate all links in the target file
- find all links that require action
- move files to target directory

## Why dot files?

When a file or folder name is preceded with a dot "." it's considered a hidden file by most operating systems. This is merely a convenience that allows configuration and other files to not clutter up simple directory listings.

When applying configuration changes to a Linux installation, it's typical to work with files both in the user's home directory and other directories such as /etc.

Not all important configuration files and folders begin with a dot. However they've come to be included in the umbrella term. 

Many users put much effort into the configuration of their systems and much of this configuration lies within these text files.

By backing these files up and versioning them we can prevent the loss of a lot of hard work and make it easier to deploy these configurations to other machines.

# How do we manage the backups?

This management model has 2 pieces. The first is centralization and the second is off-site storage.

## Centralization

In order to make storage simple, dot filer moves all interesting directories and files to a central location (your dot file repo) and then symlinks them back to their original place. Moving files, making sure the destination location is set up, and managing symlinks are all part of the dot filer model.

The way that this works is through 'target' files that live within your own dot file repository. These target files contain a list of files or folders (one per line) that are interesting targets for a specific operation.

A target can be used for a backup or restore operation.

### Backup

The backup process runs from the top of your selected targets list down and for each directory found it will move that directory into your dot file repo and create a symbolic link back to its original location.

When a target is found to be a file, it'll mirror that file's folder structure in your dot file repo and move the original file there before symlinking the file itself to the original location.

In this way your important configuration files can be found and accessed as normal while all existing within a centralized location (your dot files repo).

```shell script
$ dot-backup <target-file> <backup-repo>
```

Options:
```
--dry-run   display a report of what a backup run would do
```

### Restoration

A target file can be used to remove configuration files on your machine and create symlinks to the files in your dot file repo.

## Off-site Storage

Dot Filer has no opinion about your off-site storage technique. We're fond of making the dot file repo a git repository. But to each their own.

# About Target Files

Your repo can contain many target files. They're used as a command line argument for backup and restore commands. For example, it's entirely possible that you have a master computer that you want to back up configurations from. Then you may want to distribute these configurations to many machines.. but not ALL configurations. It's possible to have a large target file for backup and small target files for restoration of only a few configurations.

# Development

I'll fill this more out later.

Run tests

```shell script
$ composer install
$ ./test
```
