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
    const [css, setCss] = useState(layout.css);
    const [iframeDoc, setIframeDoc] = useState('');
    const [like, setLike] = useState(isLiked);
    const [buttonState, setButtonState] = useState(isLiked);
    console.log("like : ",like);
    // 送信用関数を追加
    const handleSendPosts = async (e) => {
        e.preventDefault();
        const data = await handleLayoutSave(iframeDoc.body, html, css);
        if (isOwner) {
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
        setButtonState(!buttonState);
        if (like) {
            const success = await sendLikeRequest('delete', url);
            if (success) {
                setLike(false);
            }
        } else {
            const success = await sendLikeRequest('post', url);
            if (success) {
                setLike(true);
            }
        }
    };

    return (
        <MainLayout user={props.auth.user}>
            <button type="submit" onClick={handleSendPosts}>保存</button>
            <button type="submit" class=
                {buttonState ? 
                    "bg-blue-700 text-white border border-blue-700 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center hover:ring-4 hover:outline-none hover:ring-blue-300"
                    : " bg-white text-blue-700 border border-blue-700 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center hover:ring-4 hover:outline-none hover:ring-blue-300"
                }
                onClick={handleSendLike}>
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M3 7H1a1 1 0 0 0-1 1v8a2 2 0 0 0 4 0V8a1 1 0 0 0-1-1Zm12.954 0H12l1.558-4.5a1.778 1.778 0 0 0-3.331-1.06A24.859 24.859 0 0 1 6 6.8v9.586h.114C8.223 16.969 11.015 18 13.6 18c1.4 0 1.592-.526 1.88-1.317l2.354-7A2 2 0 0 0 15.954 7Z" />
                </svg>
                <span class="sr-only">Icon description</span>
            </button>
            <Editor html={html} css={css}
                onHtmlChange={setHtml} onCssChange={setCss} />
            <Preview html={html} css={css}
                onImageChange={setIframeDoc} />
        </MainLayout>
    );
}

