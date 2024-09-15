import React, { useState, useRef, useEffect } from "react";
import { Link } from '@inertiajs/react';
import html2canvas from 'html2canvas';
import { router } from '@inertiajs/react'
import Authenticated from "@/Layouts/AuthenticatedLayout";

export default function Create(props) {
    const [html, setHtml] = useState('');
    const [css, setCss  ] = useState('');

    const iframeRef = useRef(null);

    const iframeTryCatch = (func) => {
        // iframe要素の取得
        const iframe = iframeRef.current;
        // iframeとそのcontentDocumentがnull出ない場合に実行
        try {
            if (!iframe) {
                throw new Error('Iframe element not found');
              }
            if (!iframe.contentDocument) {
                throw new Error('Iframe contentDocument not accessible');
            }
            func(iframe);
        } catch (error) {
            console.error("iframe error : ", error.message);
        }
    }
    // iframeに記述したhtmlとcssの変更を反映
    useEffect(() => {
        iframeTryCatch((iframe) => {
            const iframeDoc = iframe.contentDocument;
            iframeDoc.open();
            iframeDoc.write(`
                      <style>${css}</style>
                      ${html}
                  `);
            iframeDoc.close();
            }
        );
    }, [html, css]);
      
    // 送信用関数を追加
    const handleSendPosts = (e) => {
        e.preventDefault(); 
        iframeTryCatch((iframe) => {
            const iframeDoc = iframe.contentDocument;
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
            }).catch(function (error) {
                console.error('Error capturing the screenshot:', error);
            });
        })
        
    }
    return (
        <Authenticated user={props.auth.user} header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                Create
            </h2>
        }>
            <form onSubmit={handleSendPosts}>                
                <input type="text" name="image" id="image" value="imagedesu"/>
                <div>
                    <textarea id="html-editor" name="html" 
                    placeholder="Enter HTML here..." 
                    onChange={(e) => setHtml(e.target.value) }>
                    </textarea>
                    <textarea id="css-editor" name="css" 
                    placeholder="Enter CSS here..." 
                    onChange={(e) => setCss(e.target.value)}></textarea>
                </div>
                <button type="submit">投稿</button>                
            </form>
            <Link href="/layouts">戻る</Link>
            <iframe id="preview" class="w-full h-screen" ref={iframeRef}></iframe>
        </Authenticated>
    );
}

