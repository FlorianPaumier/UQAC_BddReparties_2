import React, {
    useEffect,
    useState
} from "react";
import {DropDown} from "./DropDown";

const MultiSelect = ({
                         itemsList,
                         selectItems,
                        setParams
                     }) => {

    const [items, setItems] = useState(itemsList);
    const [filteringList, setFilteringList] = useState(itemsList);
    const [selectedItems, setSelected] = useState(selectItems);

    // state showing if dropdown is open or closed
    const [dropdown, setDropdown] = useState(false);

    // toggle dropdown open/close
    const toogleDropdown = () => {
        setDropdown(!dropdown)
    };

    // adds new item to multiselect
    const addTag = (item) => {
        let selectItems = selectedItems.concat(item)
        setSelected(selectItems);
        setParams(selectItems)
        items.splice(items.indexOf(item), 1)
        setItems(items)
    };
    // removes item from multiselect
    const removeTag = (item) => {
        const filtered = selectedItems.filter((e) => e !== item);
        setSelected(filtered)
        const list = items
        list.splice(filteringList.indexOf(item), 0, item)
        setItems(list)
    }

    const filterItem = (e) => {
        const list = filteringList.filter(item => item.name.startsWith(e.target.value) && selectedItems.indexOf(item) < 0)
        setItems(list)
    }

    useEffect(() => {
        setItems(itemsList)
        setFilteringList(itemsList)
    }, [itemsList])
    return <div
        className="autcomplete-wrapper">
        <div
            className="autcomplete">
            <div
                className="flex flex-col items-center mx-auto">
                <div
                    className="w-full">
                    <div
                        className="flex flex-col items-center relative">
                        <div
                            className="w-full ">
                            <div
                                className="my-2 p-1 flex border border-gray-200 bg-white rounded ">
                                <div
                                    className="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 text-center">
                                    {
                                        selectedItems.map((tag, index) => {
                                            return (
                                                <div
                                                    key={index}
                                                    className="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-teal-700 bg-teal-100 border border-teal-300 ">
                                                    <div
                                                        className="text-xs font-normal leading-none max-w-full flex-initial">{tag.name}</div>
                                                    <div
                                                        className="flex flex-auto flex-row-reverse">
                                                        <div
                                                            onClick={() => removeTag(tag)}>
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="100%"
                                                                height="100%"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                                strokeWidth="2"
                                                                strokeLinecap="round"
                                                                strokeLinejoin="round"
                                                                className="feather feather-x cursor-pointer hover:text-teal-400 rounded-full w-4 h-4 ml-2">
                                                                <line
                                                                    x1="18"
                                                                    y1="6"
                                                                    x2="6"
                                                                    y2="18"/>
                                                                <line
                                                                    x1="6"
                                                                    y1="6"
                                                                    x2="18"
                                                                    y2="18"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>)
                                        })
                                    }
                                    <div
                                        className="flex-1">
                                        <input
                                            placeholder=""
                                            className="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"/>
                                    </div>
                                </div>
                                <div
                                    className="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200"
                                    onClick={toogleDropdown}>
                                    <button
                                        type="button"
                                        className="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="100%"
                                            height="100%"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            className="feather feather-chevron-up w-4 h-4">
                                            <polyline
                                                points="18 15 12 9 6 15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {dropdown ?
                        <DropDown
                            filterItem={filterItem}
                            list={items}
                            addItem={addTag}/> : null}
                </div>
            </div>

        </div>
    </div>
}

export default MultiSelect
