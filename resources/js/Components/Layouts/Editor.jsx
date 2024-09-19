import React from "react";

export default function Editor({ html, css , onHtmlChange, onCssChange}) {        
	return (
		<div class="w-full grid grid-cols-2">
            <textarea
            id="html-editor" name="html" 
            placeholder="Enter HTML here..."
            onChange={(e) => onHtmlChange(e.target.value) }>
            { html }
            </textarea>
            <textarea
            id="css-editor" name="css" 
            placeholder="Enter CSS here..." 
            onChange={(e) => onCssChange(e.target.value)}>
            { css }
            </textarea>
        </div>
	);
}