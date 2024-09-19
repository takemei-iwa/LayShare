import React, { useState } from "react";
import { Link } from '@inertiajs/react';
import Authenticated from "@/Layouts/AuthenticatedLayout";
import { router } from '@inertiajs/react'

export default function Index(props) {
    const { layouts } = props;
    console.log(layouts);
    console.log(props);
    const { d, setD } = useState(layouts[0].thumbnail);

    return (
        <Authenticated user={props.auth.user} header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                Layouts
            </h2>
        }>

            <h1> layouts</h1>
            <p>{layouts[0].id}</p> 
            <div class="grid grid-cols-3 gap-2">
            { layouts.map((layout) => (     
                    <div key={layout.id}>
                        <div class="w-full aspect-w-9 aspect-h-5 overflow-hidden">
                        <img class="object-cover w-full h-full object-left-top" alt=""
                            src = {layout.thumbnail} 
                            onClick = {() => router.get(`/layouts/${layout.id}`) }/>
                            </div>
                    </div>
                )) }
            </div>
            
            <Link href="/layouts/create">レイアウトの投稿</Link>
        </Authenticated>
    );
}