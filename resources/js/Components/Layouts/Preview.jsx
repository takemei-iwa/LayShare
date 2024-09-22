import React, { useRef, useEffect } from "react";

  export default function Preview({ html, css, onImageChange}) {        
    const iframeRef = useRef(null);
    // iframeに記述したhtmlとcssの変更を反映
    useEffect(() => {        
        const iframeDoc = iframeRef.current.contentDocument;
        iframeDoc.open();
        iframeDoc.write(`
                    <style>${css}</style>
                    ${html}
                `);
        iframeDoc.close();
        onImageChange(iframeDoc);
    }, [html, css]);

	return (
    <div class="w-full aspect-w-9 aspect-h-5">
        <iframe id="preview" class="w-full h-full" ref={iframeRef}></iframe>
    </div>
	);
}