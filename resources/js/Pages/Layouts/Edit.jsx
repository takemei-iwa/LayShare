import React, { useState } from "react";
import { router } from '@inertiajs/react'
import Authenticated from "@/Layouts/AuthenticatedLayout";

import Editor from "../../Components/Layouts/Editor";
import Preview from "../../Components/Layouts/Preview";
import handleLayoutSave from "@/Functions/Layouts/handleLayoutSave";

export default function Edit(props) {
    const { layout } = props;
    const isOwner = props.auth.user.id === layout.user_id;
    const [html, setHtml] = useState(layout.html);
    const [css, setCss  ] = useState(layout.css);
    const [iframeDoc, setIframeDoc  ] = useState('');
        
    // 送信用関数を追加
    const handleSendPosts = async (e) => {
        e.preventDefault(); 
        const data = await handleLayoutSave(iframeDoc.body, html, css);
        if(isOwner) {
            router.put(`/layouts/${layout.id}`, data)
        } else {
            router.post("/layouts/create", data);
        }                
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

