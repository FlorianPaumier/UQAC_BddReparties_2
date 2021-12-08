import React
    from "react";

const Toggle = ({
                    open,
                    text,
                    name,
                    value,
                    onChange,
    type='checkbox'
                }) => {

    return <>
        <label
            htmlFor="toggle"
            className="text-xs text-gray-700 mr-1">{text}</label>
        <div
            className=" relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
            <input
                type={type}
                name={name}
                defaultValue={value}
                defaultChecked={open}
                onChange={() => onChange(value)}
                id={`${name}-${value}`}
                className="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
            <label
                htmlFor={name}
                className="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"/>
        </div>
    </>
}

export default Toggle
