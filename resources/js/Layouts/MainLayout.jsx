import React, { useState, useEffect } from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link } from '@inertiajs/react';
import Notify from '@/Components/Layouts/Notify';

function ProfileDropDown({ user }) {
    return (
        <Dropdown>
            <Dropdown.Trigger>
                <span className="inline-flex rounded-md">
                    <button
                        type="button"
                        className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                    >
                        {user.name}

                        <svg
                            className="ms-2 -me-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fillRule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clipRule="evenodd"
                            />
                        </svg>
                    </button>
                </span>
            </Dropdown.Trigger>

            <Dropdown.Content>
                <Dropdown.Link href={route('profile.edit')}>Profile</Dropdown.Link>
                <Dropdown.Link href={route('logout')} method="post" as="button">
                    Log Out
                </Dropdown.Link>
            </Dropdown.Content>
        </Dropdown>
    );
}

function SignUpInLink() {
    return (
        <>
            <Link type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                href={route('register')}
            >
                Sign Up
            </Link>
            <Link type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                href={route('login')}
            >
                Log In
            </Link>
        </>
    );
}
export default function MainLayout({ user, header, children }) {
    console.log(user);
    const isLoggedIn = user !== null;
    const [isOpen, setIsOpen] = useState(false);
    useEffect(() => {
        // プライベートチャンネルでジョブ完了イベントをリッスン
        //     window.Echo.channel('chat')
        // .listen('UploadCompleted', (e) => {
        //     console.log("chat get");
        // });
        if(isLoggedIn){
        window.Echo.private(`user.${user.id}`)
            .listen('UploadCompleted', (e) => {
                // setNotification(e.message); // 受信したメッセージを保存
                console.log("broadcast"); // ポップアップで表示
                setIsOpen(true);
            });
        }

        // コンポーネントがアンマウントされる時にクリーンアップ
        // return () => {
        //     channel.stopListening('JobCompleted');
        // };
    }, []);
    
    const handleClose = () => { console.log("handleClose"); setIsOpen(false); }

    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);
    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="shrink-0 flex items-center">
                                <Link href="/">
                                    <ApplicationLogo className="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink href={route('index')} active={route().current('index')}>
                                    Layouts
                                </NavLink>
                                <NavLink href={route('layout.create')} active={route().current('layout.create')}>
                                    New Layout
                                </NavLink>
                                <NavLink href={route('user.likedLayouts')} active={route().current('user.likedLayouts')}>
                                    Favorites
                                </NavLink>
                                <NavLink href={route('user.layouts')} active={route().current('user.layouts')}>
                                    Your Work
                                </NavLink>
                            </div>
                        </div>

                        <div className="hidden sm:flex sm:items-center sm:ms-6">
                            <div className="ms-3 relative">
                                {isLoggedIn ? (
                                    <ProfileDropDown user={user} />
                                ) :
                                    (
                                        <SignUpInLink />
                                    )
                                }
                            </div>
                        </div>

                        <div className="-me-2 flex items-center sm:hidden">
                            <button
                                onClick={() => setShowingNavigationDropdown((previousState) => !previousState)}
                                className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        className={!showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        className={showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div className={(showingNavigationDropdown ? 'block' : 'hidden') + ' sm:hidden'}>
                    <div className="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink href={route('index')} active={route().current('index')}>
                            Layouts
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('layout.create')} active={route().current('layout.create')}>
                            New Layout
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('user.likedLayouts')} active={route().current('user.likedLayouts')}>
                            Favorites
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('user.layouts')} active={route().current('user.layouts')}>
                            Your Work
                        </ResponsiveNavLink>
                    </div>

                    <div className="pt-4 pb-1 border-t border-gray-200">
                        {isLoggedIn &&
                            (
                                <div className="px-4">
                                    <div className="font-medium text-base text-gray-800">{user.name}</div>
                                    <div className="font-medium text-sm text-gray-500">{user.email}</div>
                                </div>
                            )
                        }

                        <div className="mt-3 space-y-1">
                            {isLoggedIn ?
                                (<>
                                    <ResponsiveNavLink href={route('profile.edit')}>Profile</ResponsiveNavLink>
                                    <ResponsiveNavLink method="post" href={route('logout')} as="button">
                                        Log Out
                                    </ResponsiveNavLink>
                                </>) : (
                                    <SignUpInLink />
                                )
                            }
                        </div>
                    </div>
                </div>
            </nav>

            {header && (
                <header className="bg-white shadow">
                    <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{header}</div>
                </header>
            )}

            <main>
                <Notify
                    isOpen={isOpen}
                    onClose={handleClose}
                    message="This is a pop-up notification!"
                />
                {children}
            </main>
        </div>
    );
}