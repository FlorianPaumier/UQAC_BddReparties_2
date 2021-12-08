import React, {
    useEffect
} from 'react';

const PageHeader = ({params, setParams, path, title}) => {
    const addParams = (value) => {
        let query = params;
        query['start_with'] = value
        setParams({...query})
    }

    const generateLinks = () => {
        const letters = "#ABCDEFGHIJKLMNOPQRSTUVWXYZ"
        return letters.split("").map((letter, key) => {
            return <li
                key={letter}
                onClick={() => addParams(letter)}
                className={`text-center px-4 cursor-pointer
            ${key > 0 ? "border-l-4 border-black" : ""}
             ${params["letter"] === letter ? "text-xl underline" : ''}`}>
                {letter}
            </li>
        })
    }

    return <div
        className="2xl:container 2xl:mx-auto">
        <div
            className="py-6 lg:px-20 md:px-6 px-4">
            <div
                className="px-4 md:px-6 py-16 lg:px-20 2xl:px-0 2xl:mx-auto 2xl:container">
                <div
                    className="flex justify-start items-start flex-col space-y-2">
                    <p className="text-3xl lg:text-4xl font-semibold leading-9 text-gray-800 dark:text-white">{title}</p>
                    <p className="text-base leading-4 text-gray-600 dark:text-gray-300">{path}</p>
                </div>
            </div>
            <ul className="md:flex-row md:flex hidden w-100 justify-between mx-8 text-center">
                {generateLinks()}
            </ul>
            <select
                name=""
                className="md:hidden"
                id=""></select>
        </div>
    </div>
}

export default PageHeader;
