## Simple Example with Default Chunks

### Now place a simple Snippet call in a Resource:
```html
[[!menuBuilder?
    &startId=`1`
    &displayStart=`1`
    &debug=`1`
    &debugSql=`0`
]]
```

Save and go to the page and you should have a menu!


## Simple Example with Custom Chunks


### Create a Chunk to be the wrapper named: mbWrapperTest

```html
<ul class="[[+mbClasses]]">
  Count: [[+mbCount]] | Level: [[+mbLevel]]
  
  [[+mbChildren]]
  
</ul>
```

### Create a Chunk to be the item, named: mbItemTest
```html
<li>
	Count: [[+mbCount]] | Depth: [[+mbLevel]] <a href="[[+mbUrl]]" class="[[+mbItemClasses]]">[[+mbTitle]]</a>
	[[+mbChildren]]
</li>
```

### Now place a simple Snippet call in a Resource:
```html
[[!menuBuilder?
    &startId=`1`
    &displayStart=`1`
    &chunkItem=`mbItemTest`
    &chunkWrapper=`mbWrapperTest`
    &debug=`1`
    &debugSql=`0`
    &rawTvs=`relatedProducts`
]]
```

Save and go to the page and you should have a menu!