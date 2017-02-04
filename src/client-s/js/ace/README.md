## ACE Emmet Extension = `ext-emmet.min.js`

This was taken from [the original](https://raw.githubusercontent.com/ajaxorg/ace-builds/master/src-min-noconflict/ext-emmet.js) and tweaked just slightly. I removed `,"resources","resources","tabStops","resources","utils","actions"` from the original extension as advised [here](https://github.com/ajaxorg/ace/issues/2800).

I also added `|markdown` to the list of supported modes; i.e., in the `isSupportedMode()` function in this file.

This was testing and working against ACE v1.2.6 via CDNjs.
https://cdnjs.com/libraries/ace/1.2.6
