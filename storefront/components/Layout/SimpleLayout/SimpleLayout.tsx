import { Webline } from 'components/Layout/Webline/Webline';

type SimpleLayoutProps = {
    heading: string;
    standardWidth?: true;
};

export const SimpleLayout: FC<SimpleLayoutProps> = ({ heading, children, standardWidth }) => (
    <Webline>
        <h1>{heading}</h1>

        {standardWidth ? (
            children
        ) : (
            <div className="flex w-full justify-center">
                <div className="my-7 w-full rounded bg-backgroundMore px-2 pb-4 pt-5 lg:w-[690px] lg:px-14 lg:pb-8 lg:pt-10">
                    {children}
                </div>
            </div>
        )}
    </Webline>
);
