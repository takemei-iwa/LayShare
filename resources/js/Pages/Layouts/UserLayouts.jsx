import React, { useState } from "react";
import { Link } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import MainLayout from "@/Layouts/MainLayout";

export default function UserLayouts(props) {
    const { layouts } = props;
    console.log(layouts);
    console.log("Index props : ",props.auth.user);

    return (
        <MainLayout user={props.auth.user}>
            <h1> layouts</h1>
            <p>{layouts[0].id}</p> 
            <div class="grid grid-cols-3 gap-2">
            { layouts.map((layout) => (     
                    <div key={layout.id}>
                        <div class="w-full aspect-w-9 aspect-h-5 overflow-hidden bg-white">
                        <img class="object-cover w-full h-auto object-left-top" alt=""
                            src = {layout.thumbnail} 
                            onClick = {() => router.get(`/layouts/${layout.id}`) }/>
                            </div>
                    </div>
                )) }
            </div>
            
            <Link href="/layouts/create">レイアウトの投稿</Link>
        </MainLayout>
    );
}