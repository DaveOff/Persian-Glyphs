# Persian-Glyphs

<div align="center">

<a href="#"><img src="https://raw.githubusercontent.com/DaveOff/Persian-Glyphs/main/Persian_Glyphs.png" title="" alt=""></a>

</div>

### Purpose
This class takes Persian text (encoded in Windows-1256 character 
set) as input and performs Persian glyph joining on it and outputs 
a UTF-8 hexadecimals stream that is no longer logically arranged but in a visual order which gives readable results.


### Quick Start

```php
require 'Glyphs.php';
$obj = new Persian_Glyphs();
echo $obj->utf8Glyphs("سلام");
```
