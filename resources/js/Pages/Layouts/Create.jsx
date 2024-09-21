import React, { useState } from "react";
import { router } from '@inertiajs/react'
import Authenticated from "@/Layouts/AuthenticatedLayout";

import Editor from "../../Components/Layouts/Editor";
import Preview from "../../Components/Layouts/Preview";

import handleLayoutSave from "@/Functions/Layouts/handleLayoutSave";

export default function Create(props) {
    const [html, setHtml] = useState('');
    const [css, setCss  ] = useState('');
    const [iframeDoc, setIframeDoc  ] = useState('');
        
    // 送信用関数を追加
    const handleSendPosts = async (e) => {
        e.preventDefault(); 
        const data = await handleLayoutSave(iframeDoc.body, html, css);
        console.log("layoutdata : ", data);
        router.post("/layouts/create", data);
        
    }
    return (
        <Authenticated user={props.auth.user} header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                Create
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

