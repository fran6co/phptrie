PHPTrie
=======

A PHP implementation of a Trie, which is a data structure mainly used for fast string keys searches.

How to use PHPTrie
======

Creating a new PHPTrie structure
------

	<?php
	use PHPTrie\Trie;
	$trie = new Trie();
	?>

This creates an empty Trie.

Inserting elements
------

You can add entries to the PHPTrie by specifying a key and a value.

	$trie = new Trie();
	$trie->add("key", 10);

or

	$trie = new Trie();
	$trie->add("This can be any string!", $myArray);

or

	$trie = new Trie();
	$trie->add("Make sure it's a string...", $stdClassObject);

Your values should probably have a consistent type throughout the whole trie, but exactly how you structure your values is up to you.

**From here on, we will assume that the FileTrie object has been constructed and is called `$trie`.**

**Overwrite values**

By default the add method overwrites the values if already exists, if you want to avoid that you can use

    $trie->add("blah", 11, false);




