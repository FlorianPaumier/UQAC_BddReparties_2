import React
    from "react";

export const DropDown = ({list, addItem, filterItem}) => {
    return <div id="dropdown" className="absolute shadow top-100 bg-white z-40 w-max lef-0 rounded max-h-96">
        <div
            className="cursor-pointer border-gray-100 rounded-t border-b hover:bg-teal-100 absolute top">
            <input
                onChange={filterItem} type="text" className={"border-1 border-black h-8 px-2"} placeholder={"Filtrer"}/>
        </div>
        <div className="flex flex-col overflow-y-auto max-h-80 mt-8">
            { list.map((item, key) => {
                return <div key={key}
                            className="cursor-pointer border-gray-100 rounded-t border-b hover:bg-teal-100"
                            onClick={() => addItem(item)}>
                    <div className="flex items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-teal-100" >
                        <div className="items-center flex">
                            <div className="mx-2 leading-6  ">
                                { item }
                            </div>
                        </div>
                    </div>
                </div>
            })}
        </div>
    </div>
}
