import React
    from "react";
const SpellCard = ({spell}) => {
    return <div key={spell.name}
        className="relative">
        <div
            className="absolute top-0 left-0 py-2 px-4 bg-white bg-opacity-50">
            <p className="text-xs leading-3 text-gray-800">
                New</p>
        </div>
        <div
            className="relative group">
            <div
                className="flex justify-center items-center opacity-0 bg-gradient-to-t from-gray-800 via-gray-800 to-opacity-30 group-hover:opacity-50 absolute top-0 left-0 h-full w-full"/>
            <img
                className="w-full"
                src={`/images/${ spell.image ?? "back.png" }`}
                alt="A girl Posing Image"/>
            <div
                className="absolute bottom-0 p-8 w-full opacity-0 group-hover:opacity-100">
                <a href={`/spell/${spell.id}`}>
                    <button
                        className="bg-transparent font-medium text-base leading-4 border-2 border-white py-3 w-full mt-2 text-white">
                        Quick
                        View
                    </button>
                </a>
            </div>
        </div>
        <p className="font-normal dark:text-white text-xl leading-5 text-gray-800 md:mt-6 mt-4">
            {
                spell.name
            }</p>
        <p className="font-semibold dark:text-gray-300 text-xl leading-5 text-gray-800 mt-4">
            {
                spell.schoolName
            }</p>
        <p className="font-semibold dark:text-gray-300 text-xl leading-5 text-gray-800 mt-4">
            {
                spell.subSchoolsName !== null && spell.subSchoolName.length > 0 ? spell.subSchoolName : 'N/C'
            }</p>
        <p className="font-normal dark:text-gray-300 text-base leading-4 text-gray-600 mt-4">
            {spell.shortDescription.length > 1 ? spell.shortDescription : spell.description.slice(0,50)+'...'}</p>
    </div>
}


export default SpellCard;
