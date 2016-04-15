/*
 * This file is part of the EcoLearnia platform.
 *
 * (c) Young Suk Ahn Park <ys.ahnpark@mathnia.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * EcoLearnia v0.0.2
 *
 * @fileoverview
 *  This file includes the definition of ItemPlayer class.
 *
 * @author Young Suk Ahn Park
 * @date 5/13/15
 */

 import AbstractResource from './abstract-resource';

 /**
  * @class ContentResource
  *
  * @module studio
  *
  * @classdesc
  *  Content Resource.
  *
  */
 export default class ContentResource extends AbstractResource
 {
     /**
      * @param {object} config
      */
     constructor(config)
     {
        super(config);
     }
 }
