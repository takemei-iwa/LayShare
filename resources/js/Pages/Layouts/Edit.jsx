import React, { useState } from "react";
import { router } from '@inertiajs/react'
import axios from 'axios';
import MainLayout from "@/Layouts/MainLayout";

import Editor from "../../Components/Layouts/Editor";
import Preview from "../../Components/Layouts/Preview";
import handleLayoutSave, { handleGuest } from "@/Functions/Layouts/handleLayoutSave";

export default function Edit(props) {
    const { layout, isLiked } = props;
    const isOwner = props.auth.user && props.auth.user.id === layout.user_id;
    const [html, setHtml] = useState(layout.html);
    const [css, setCss  ] = useState(layout.css);
    const [iframeDoc, setIframeDoc  ] = useState('');
    const [like, setLike  ] = useState(isLiked);
    
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
    const sendLikeRequest = async (method, url) => {
        try {
            if (method === 'delete') {
                await axios.delete(url);
            } else if (method === 'post') {
                await axios.post(url);
            }
            return true;
        } catch (error) {
            if (error.response && error.response.status === 401) {
                router.get(route("login"));
            } else {
                console.log("Error: ", error);
            }
            return false;
        }
    };
    
    const handleSendLike = async (e) => {
        e.preventDefault();
        const url = `/layouts/${layout.id}/like`;
    
        if (like) {
            const success = await sendLikeRequest('delete', url);
            if (success){
                setLike(false);
            }
        } else {
            const success = await sendLikeRequest('post', url);
            if (success){
                setLike(true);
            }
        }
    };
    
    return (
        <MainLayout user={props.auth.user}>
            <button type="submit" onClick={handleSendPosts}>保存</button>   
            <button type="submit" onClick={handleSendLike}>いいね</button>                         
            <Editor html={html} css={css} 
                onHtmlChange={setHtml} onCssChange={setCss} />            
            <Preview html={html} css={css}
                onImageChange={setIframeDoc} />
        </MainLayout>
    );
}

