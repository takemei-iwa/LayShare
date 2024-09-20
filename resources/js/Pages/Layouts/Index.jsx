import React, { Children, useState } from "react";
import { Link } from '@inertiajs/react';
import Authenticated from "@/Layouts/AuthenticatedLayout";
import GuestLayout from "@/Layouts/GuestLayout";
import { router } from '@inertiajs/react'

function MainLayout( { user, children }) {
    console.log(user);
    const isLoggedIn = user !== null;
    if (isLoggedIn) {
        return(
            <Authenticated user={user}>
                {children}
            </Authenticated>
        );
    } else{
        return(
            <GuestLayout>
                {children}
            </GuestLayout>
        );
    }
}
export default function Index(props) {
    const { layouts } = props;
    console.log(layouts);
    console.log("Index props : ",props.auth.user);
    const isLoggedIn = props.auth.user !== null;
    const { d, setD } = useState(layouts[0].thumbnail);

    return (
        <MainLayout user={props.auth.user}>
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
        </MainLayout>
    );
}