import React
    from "react";
const BeastCard = ({monster}) => {
    return <div
        className="mb-4 xl:px-2">
        <div
            className="bg-white dark:bg-gray-800 shadow xl:flex lg:flex md:flex p-5 rounded">
            <div
                className="xl:w-3/6 lg:w-3/6 md:w-3/6 mb-4 xl:mb-0 lg:mb-0 md:mb-0">
                <a tabIndex="0"
                   className="text-gray-800 dark:text-gray-100"
                   href="{{ path('beast_show', {'id' : monster.id}) }}">
                    <p className="text-lg  mb-3 font-normal underline">{monster.name}</p>
                </a>
                <p tabIndex="0"
                   className="focus:outline-none text-sm text-gray-600 dark:text-gray-400 font-normal">{monster.description.slice(0,50)}...</p>
            </div>
            <div
                className="xl:w-3/6 lg:w-3/6 md:w-3/6 flex justify-end flex-col xl:items-end lg:items-end md:items-end items-start">
                <p tabIndex="0"
                   className="focus:outline-none text-xs text-indigo-700 bg-indigo-200 px-3 rounded mb-2 font-normal py-1">
                    {monster.typeName ? monster.typeName : "N/C" }</p>
                <p tabIndex="0"
                   className="focus:outline-none text-sm text-gray-600 dark:text-gray-400 font-normal">{monster.subTypeName ? monster.subTypeName : "N/C" }</p>
            </div>
        </div>
    </div>
}


export default BeastCard;
