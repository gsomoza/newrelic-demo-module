NewRelic Magento Demo Module
====================

This is a demonstration module created to accompany a guest post in the NewRelic Blog. A link to the post will be
supplied when available.

The module creates a Magento Widget that can be inserted into any CMS page or Static Block. It shows a list with links
to all products that are marked with the custom attribute "featured". The custom attribute must be created and
assigned to some products before testing the module.

The Widget is called
"Featured Products URL List" and has two different render modes:

* Slow (default)
* Fast / Optimized

The "slow" mode uses a N+1 query to load the list of products, which causes a huge impact on performance. In our tests,
slow mode causes the page to render 3000ms slower. It is used to demonstrate NewRelic features throughout the tutorial.

The "fast" mode uses Mage best-practices to load the products. In our test server they're loaded in 120ms.
