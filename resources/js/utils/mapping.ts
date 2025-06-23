import { Component } from 'vue';

import FileInput from '@/components/admin/Form/FileInput.vue';
import GalleryInput from '@/components/admin/Form/GalleryInput.vue';
import ImageInput from '@/components/admin/Form/ImageInput.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import MarkdownInput from '@/components/admin/Form/MarkdownInput.vue';
import RepeaterInput from '@/components/admin/Form/RepeaterInput.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import VideoInput from '@/components/admin/Form/VideoInput.vue';
import IconBlock from '@/components/admin/Icon/IconBlock.vue';
import IconCheck from '@/components/admin/Icon/IconCheck.vue';
import IconCollection from '@/components/admin/Icon/IconCollection.vue';
import IconMedia from '@/components/admin/Icon/IconMedia.vue';
import IconMenu from '@/components/admin/Icon/IconMenu.vue';
import IconPage from '@/components/admin/Icon/IconPage.vue';
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import IconSquareArrowRight from '@/components/admin/Icon/IconSquareArrowRight.vue';

export const getInputCpt = (fieldType: string) => {
    const cptMap: { [key: string]: Component } = {
        textarea: TextAreaInput,
        file: FileInput,
        image: ImageInput,
        repeater: RepeaterInput,
        markdown: MarkdownInput,
        gallery: GalleryInput,
        video: VideoInput,
    };

    return cptMap[fieldType] || InputLabel;
};

export const getIconCpt = (icon: string) => {
    const cptMap: { [key: string]: Component } = {
        plus: IconPlus,
        check: IconCheck,
        'square-arrow': IconSquareArrowRight,
        page: IconPage,
        collection: IconCollection,
        block: IconBlock,
        media: IconMedia,
        menu: IconMenu,
    };

    return cptMap[icon] || IconPlus;
};

export const getCollectionPage = (type: string) => {
    const cptMap: { [key: string]: Component } = {};

    return cptMap[type];
};
