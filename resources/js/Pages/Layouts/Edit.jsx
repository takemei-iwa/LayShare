import React, { useState } from "react";
import html2canvas from 'html2canvas';
import { router } from '@inertiajs/react'
import Authenticated from "@/Layouts/AuthenticatedLayout";

import Editor from "../../Components/Layouts/Editor";
import Preview from "../../Components/Layouts/Preview";

export default function Edit(props) {
    const { layout } = props;
    const [html, setHtml] = useState(layout.html);
    const [css, setCss  ] = useState(layout.css);
    const [iframeDoc, setIframeDoc  ] = useState('');
        
    // 送信用関数を追加
    const handleSendPosts = (e) => {
        e.preventDefault(); 
        let imageUrl = "";
        // Make sure the iframe's content is fully loaded before capturing
        html2canvas(iframeDoc.body).then(function (canvas) {
            const img = canvas.toDataURL('image/png');
            imageUrl = img;
            const data = {
                image: imageUrl, 
                html: html,
                css: css,                
            }
            router.post("/layouts/create", data);
        })        
    }
    return (
        <Authenticated user={props.auth.user} header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                Edit
            </h2>
        }>
            <button type="submit" onClick={handleSendPosts}>保存</button>                            
            <Editor html={html} css={css} 
                onHtmlChange={setHtml} onCssChange={setCss} />            
            <Preview html={html} css={css}
                onImageChange={setIframeDoc} />
        </Authenticated>
    );
}

