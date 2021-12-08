import React
    , {useState} from "react";
import ReactDOM
    from "react-dom";
import SpellFilter
    from "./components/FullSearch/SpellFilter";
import BeastFilter
    from "./components/FullSearch/BeastFilter";
import ClassFilter
    from "./components/FullSearch/ClassFilter";
import Toggle
    from "./components/Toggle";
import Pagination
    from "./components/Pagination";
import SpellCard
    from "./components/Spell/SpellCard";

const FullSearch = () => {

    const types = [{
        name: "search-input",
        value: 'class',
        text: 'Classes'
    }, {
        name: "search-input",
        value: 'spell',
        text: 'Spells'
    }, {
        name: "search-input",
        value: 'beast',
        text: 'Bestiary'
    }]

    const [toggle, setToggle] = useState({
        'class': false,
        'beast': false,
        'spell': true,
    });

    const [items, setItems] = useState([]);

    const toggleFilter = (key) => {
        Object.keys(toggle).forEach((index) => toggle[index] = false)
        console.log(toggle)
        toggle[key] = !toggle[key]
        setToggle({...toggle})
    }

    return <section>
        <h2 className={"w-full text-center text-2xl"}>Full
            Search</h2>
        <section
            className={"border-b"}>
            <ul className={"w-full grid grid-cols-3"}>
                {types.map(type => {
                    return <li
                        className={"text-center justify-center"}>
                        <Toggle
                            value={type.value}
                            name={type.name}
                            text={type.text}
                            open={toggle[type.value]}
                            onChange={toggleFilter}
                            type={'radio'}/>
                    </li>
                })}
            </ul>
        </section>
        <section
            className={'py-12'}>
            <SpellFilter
                open={toggle.spell}
                setItems={setItems}
            />
            <BeastFilter
                open={toggle.beast}/>
            <ClassFilter
                open={toggle.class}/>
        </section>
        {items.length > 0 && (
            <div>{items.length} Résultats trouvés</div>
        )}

        {items.length === 0 && (
            <span
                className={"text-2xl"}>Waiting</span>)}
        <div
            className="px-4 grid lg:grid-cols-8 sm:grid-cols-4 grid-cols-1 lg:gap-y-12 lg:gap-x-8 sm:gap-y-10 sm:gap-x-6 gap-y-6 lg:mt-12 mt-10 mb-4">
            {toggle.spell && (
                items.map(spell =>
                    <SpellCard
                        spell={spell}/>)
            )}
        </div>
    < /section>
}


ReactDOM.render(
    <FullSearch/>,
    document.getElementById('fullSearch'));
