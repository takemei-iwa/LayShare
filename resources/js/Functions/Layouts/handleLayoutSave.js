import html2canvas from 'html2canvas';
import { router } from '@inertiajs/react'

const createLayoutData = async (iframeBody, html, css) => {
    try {
        const canvas = await html2canvas(iframeBody);
        const imageUrl = canvas.toDataURL('image/png');
        return {
            image: imageUrl, 
            html: html,
            css: css,                
        };
    } catch(error) {
        console.error('Error html2canvas :', error);      
    };  
};

export const handleGuest = (user) => {
    if(!user) { 
        router.get("/register");
        return true;
    }
    return false;
}

const handleLayoutSave = async (iframeBody, html, css) => {
    return await createLayoutData(iframeBody, html, css);
}

export default handleLayoutSave;